<x-app-layout>

    <h1>Stock Data</h1>
    <table border="1">
        <thead>
        <tr>
            <th>ID</th>
            <th>Stock Name</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($stocks as $stock)
            <tr>
                <td>{{ $stock->id }}</td>
                <td>{{ $stock->stock_name }}</td>
                <td>{{ $stock->current_price }}</td>
            </tr>
        @endforeach
        </tbody>

    </table>

    {{$stocks->links()}}

</x-app-layout>