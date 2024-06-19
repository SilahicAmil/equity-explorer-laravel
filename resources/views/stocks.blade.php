<x-app-layout>

    <h1>Stock Data</h1>
    <table border="1">
        <thead>
        <tr>
            <th>ID</th>
            <th>Stock Name</th>
            <th>Price</th>
            <th>Buy Stock</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($stocks as $stock)
            <tr>
                <td>{{ $stock->id }}</td>
                <td>{{ $stock->stock_name }}</td>
                <td>{{ $stock->current_price }}</td>
                <td><a href="/buy-stock?stock_id={{ $stock->id }}">Buy</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

</x-app-layout>