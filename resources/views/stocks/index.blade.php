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
                <td>
                    <form action="{{ route('stock-transactions.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="stock_name" value="{{ $stock->stock_name }}">
                        <input type="hidden" name="stock_price" value="{{ $stock->current_price }}">
                        <input type="hidden" name="num_stock_traded" value="10">
                        <input type="hidden" name="transaction_total" value="{{ $stock->current_price }}">
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                        <label for="buy">Buy</label>
                        <label>
                            <input type="radio" name="transaction_type" value="buy" required>
                        </label>
                        <label for="sell">Sell</label>
                        <label>
                            <input type="radio" name="transaction_type" value="sell" required>
                        </label>

                        <button type="submit">Submit</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{$stocks->links()}}
</x-app-layout>