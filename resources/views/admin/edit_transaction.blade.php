@extends('layouts.app')

@section('content')
<h2>Edit Transaksi</h2>
<form method="POST" action="{{ route('admin.transactions.update', $transaction) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="user_id" class="form-label">User</label>
        <select class="form-select" id="user_id" name="user_id" required>
            <option value="">Pilih User</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ (old('user_id') ?? $transaction->user_id) == $user->id ? 'selected' : '' }}>
                    {{ $user->nama }} ({{ $user->kode_user }})
                </option>
            @endforeach
        </select>
        @error('user_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="bulan" class="form-label">Bulan</label>
        <select class="form-select" id="bulan" name="bulan" required>
            <option value="">Pilih Bulan</option>
            @foreach($months as $month)
                <option value="{{ $month }}" {{ (old('bulan') ?? $transaction->bulan) == $month ? 'selected' : '' }}>
                    {{ $month }}
                </option>
            @endforeach
        </select>
        @error('bulan')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="year" class="form-label">Tahun</label>
        <select class="form-select" id="year" name="year" required>
            <option value="">Pilih Tahun</option>
            @foreach($years as $year)
                <option value="{{ $year }}" {{ (old('year') ?? $transaction->year) == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>
        @error('year')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="pencicilan_rutin" class="form-label">Pencicilan Rutin</label>
        <input type="number" step="0.01" class="form-control" id="pencicilan_rutin" name="pencicilan_rutin" value="{{ old('pencicilan_rutin') ?? $transaction->pencicilan_rutin }}" required>
        @error('pencicilan_rutin')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="pencicilan_bertahap" class="form-label">Pencicilan Bertahap</label>
        <input type="number" step="0.01" class="form-control" id="pencicilan_bertahap" name="pencicilan_bertahap" value="{{ old('pencicilan_bertahap') ?? $transaction->pencicilan_bertahap }}" required>
        @error('pencicilan_bertahap')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Perbarui Transaksi</button>
    <a href="{{ route('admin.transactions') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection