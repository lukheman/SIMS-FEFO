<div>
    @if ($status === \App\Constants\StatusPembayaran::LUNAS)
    <span class="badge bg-success">{{ $status->label() }}</span>
    @elseif($status === \App\Constants\StatusPembayaran::BELUMBAYAR)
    <span class="badge bg-danger">{{ $status->label() }}</span>
    @endif
</div>
