"""
Script Transfer Data: sims_fefo_lama → sims_fefo
=================================================
Memindahkan data dari database lama (ERD v1) ke database baru (ERD v2).

Perbedaan struktur yang dihandle:
  - persediaan : kolom baru tanggal_exp & tanggal_masuk → diisi NULL / default
  - mutasi     : kolom baru id_persediaan → diisi NULL (tidak ada padanan di lama)

Urutan insert mengikuti dependency FK agar tidak terjadi constraint error.

Cara pakai:
  pip install mysql-connector-python
  python transfer_data.py

  Atau override via env var:
  DB_HOST / DB_PORT / DB_USER / DB_PASSWORD
"""

import mysql.connector
import os
import datetime

# ─────────────────────────────────────────────────────────────
#  KONFIGURASI — sesuaikan atau set via environment variable
# ─────────────────────────────────────────────────────────────
DB_HOST     = os.getenv("DB_HOST",     "localhost")
DB_PORT     = int(os.getenv("DB_PORT", 3306))
DB_USER     = os.getenv("DB_USER",     "root")
DB_PASSWORD = os.getenv("DB_PASSWORD", "akmal")

DB_LAMA = "sims_fefo_lama"   # database sumber  (ERD v1)
DB_BARU = "sims_fefo"        # database tujuan  (ERD v2)

# Jika True, data di DB_BARU akan dihapus dulu sebelum insert
TRUNCATE_BEFORE_INSERT = True

# ─────────────────────────────────────────────────────────────
#  URUTAN TABEL  (parent harus lebih dulu dari child)
# ─────────────────────────────────────────────────────────────
# Format: (nama_tabel, [kolom_tujuan], query_sumber)
# Gunakan None pada query_sumber untuk pakai SELECT * otomatis.
# ─────────────────────────────────────────────────────────────

TRANSFER_PLAN = [
    # ── Tabel tanpa FK (parent) ──────────────────────────────
    {
        "table"  : "users",
        "src_sql": "SELECT * FROM users",
        "columns": None,   # None = ambil semua kolom dari sumber, cocokkan ke tujuan
    },
    {
        "table"  : "reseller",
        "src_sql": "SELECT * FROM reseller",
        "columns": None,
    },
    {
        "table"  : "produk",
        "src_sql": "SELECT * FROM produk",
        "columns": None,
    },
    # ── persediaan: tambah tanggal_exp & tanggal_masuk (NULL) ─
    {
        "table"  : "persediaan",
        "src_sql": """
            SELECT
                id,
                id_produk,
                jumlah,
                NULL        AS tanggal_exp,    -- kolom baru di ERD v2
                NULL        AS tanggal_masuk,  -- kolom baru di ERD v2
                created_at,
                updated_at
            FROM persediaan
        """,
        "columns": [
            "id", "id_produk", "jumlah",
            "tanggal_exp", "tanggal_masuk",
            "created_at", "updated_at",
        ],
    },
    {
        "table"  : "restock",
        "src_sql": "SELECT * FROM restock",
        "columns": None,
    },
    {
        "table"  : "keranjang_belanja",
        "src_sql": "SELECT * FROM keranjang_belanja",
        "columns": None,
    },
    {
        "table"  : "transaksi",
        "src_sql": "SELECT * FROM transaksi",
        "columns": None,
    },
    # ── mutasi: tambah id_persediaan (NULL) ──────────────────
    {
        "table"  : "mutasi",
        "src_sql": """
            SELECT
                id,
                id_produk,
                NULL        AS id_persediaan,  -- kolom baru di ERD v2
                jumlah,
                tanggal,
                jenis,
                keterangan,
                satuan,
                created_at,
                updated_at
            FROM mutasi
        """,
        "columns": [
            "id", "id_produk", "id_persediaan",
            "jumlah", "tanggal", "jenis",
            "keterangan", "satuan",
            "created_at", "updated_at",
        ],
    },
    {
        "table"  : "pesanan",
        "src_sql": "SELECT * FROM pesanan",
        "columns": None,
    },
]


# ─────────────────────────────────────────────────────────────
#  HELPER
# ─────────────────────────────────────────────────────────────

def log(msg: str):
    ts = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    print(f"[{ts}] {msg}")


def get_conn(database: str):
    return mysql.connector.connect(
        host=DB_HOST, port=DB_PORT,
        user=DB_USER, password=DB_PASSWORD,
        database=database,
        autocommit=False,
    )


def table_columns(cursor, table: str):
    """Ambil daftar kolom tabel dari information_schema."""
    cursor.execute("""
        SELECT COLUMN_NAME
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = %s
        ORDER BY ORDINAL_POSITION
    """, (table,))
    return [r[0] for r in cursor.fetchall()]


# ─────────────────────────────────────────────────────────────
#  TRANSFER SATU TABEL
# ─────────────────────────────────────────────────────────────

def transfer_table(plan: dict, conn_src, conn_dst):
    table   = plan["table"]
    src_sql = plan["src_sql"].strip()

    cur_src = conn_src.cursor(dictionary=True)
    cur_dst = conn_dst.cursor()

    # 1. Ambil data dari sumber
    cur_src.execute(src_sql)
    rows = cur_src.fetchall()

    if not rows:
        log(f"  ⚠  '{table}' — kosong di sumber, dilewati.")
        cur_src.close(); cur_dst.close()
        return 0

    # 2. Tentukan kolom tujuan
    if plan["columns"]:
        cols = plan["columns"]
    else:
        # Ambil kolom tujuan dari DB baru, filter yang ada di hasil query
        dst_cols = table_columns(cur_dst, table)
        sample   = list(rows[0].keys())
        cols     = [c for c in dst_cols if c in sample]

    # 3. Truncate jika diminta
    if TRUNCATE_BEFORE_INSERT:
        cur_dst.execute(f"DELETE FROM `{table}`")
        log(f"  🗑  '{table}' — data lama dihapus ({cur_dst.rowcount} baris).")

    # 4. Bulk insert dengan executemany
    placeholders = ", ".join(["%s"] * len(cols))
    col_names    = ", ".join(f"`{c}`" for c in cols)
    insert_sql   = f"INSERT INTO `{table}` ({col_names}) VALUES ({placeholders})"

    data = [tuple(row.get(c) for c in cols) for row in rows]

    BATCH = 500
    inserted = 0
    for i in range(0, len(data), BATCH):
        cur_dst.executemany(insert_sql, data[i:i+BATCH])
        inserted += cur_dst.rowcount

    conn_dst.commit()
    log(f"  ✓  '{table}' — {inserted} baris berhasil dipindahkan.")
    cur_src.close(); cur_dst.close()
    return inserted


# ─────────────────────────────────────────────────────────────
#  MAIN
# ─────────────────────────────────────────────────────────────

def main():
    log("=" * 60)
    log(f"  Transfer Data: {DB_LAMA}  →  {DB_BARU}")
    log("=" * 60)

    conn_src = get_conn(DB_LAMA)
    conn_dst = get_conn(DB_BARU)

    # Nonaktifkan FK check sementara agar truncate & insert urutan bebas
    cur = conn_dst.cursor()
    cur.execute("SET FOREIGN_KEY_CHECKS = 0")
    cur.close()
    log("FK check dinonaktifkan sementara.\n")

    total = 0
    errors = []

    for plan in TRANSFER_PLAN:
        try:
            log(f"── Memproses tabel '{plan['table']}' ...")
            n = transfer_table(plan, conn_src, conn_dst)
            total += n
        except Exception as e:
            conn_dst.rollback()
            log(f"  ✗ ERROR pada '{plan['table']}': {e}")
            errors.append((plan["table"], str(e)))

    # Aktifkan kembali FK check
    cur = conn_dst.cursor()
    cur.execute("SET FOREIGN_KEY_CHECKS = 1")
    cur.close()
    conn_dst.commit()
    log("\nFK check diaktifkan kembali.")

    conn_src.close()
    conn_dst.close()

    # Ringkasan
    log("\n" + "=" * 60)
    log(f"  SELESAI — Total {total} baris dipindahkan.")
    if errors:
        log(f"  ⚠  {len(errors)} tabel mengalami error:")
        for tbl, err in errors:
            log(f"     • {tbl}: {err}")
    else:
        log("  ✅ Semua tabel berhasil tanpa error.")
    log("=" * 60)


if __name__ == "__main__":
    main()
