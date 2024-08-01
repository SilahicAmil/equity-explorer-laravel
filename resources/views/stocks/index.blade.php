@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <livewire:stocks.stocks-dashboard :stocks="$stocks"/>
        {{ $stocks->links() }}
    </div>
@endsection
