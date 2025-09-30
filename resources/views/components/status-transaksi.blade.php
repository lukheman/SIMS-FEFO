<div>
    @if ($status === \App\Constants\StatusTransaksi::PENDING)
    <span class="badge bg-secondary">{{ $status }}</span>
    @elseif($status === \App\Constants\StatusTransaksi::DIPROSES)
    <span class="badge bg-success">{{ $status }}</span>
    @elseif($status === \App\Constants\StatusTransaksi::DIKIRIM)
    <span class="badge bg-warning">{{ $status }}</span>
    @elseif($status === \App\Constants\StatusTransaksi::DIKIRIM)
    <span class="badge bg-orange">{{ $status }}</span>
    @elseif($status === \App\Constants\StatusTransaksi::SELESAI)
    <span class="badge bg-green">{{ $status }}</span>
    @elseif($status === \App\Constants\StatusTransaksi::DITERIMA)
    <span class="badge bg-primary">{{ $status }}</span>
    @endif
</div>
