
    <div class="container mx-auto p-4">
        @if (session()->has('transaction_success'))
            <!-- Success message display logic -->
            <div class="alert alert-success">
                {{ session('transaction_success') }}
            </div>
        @endif

        @if ($errors->any())
            <!-- Error message display logic -->
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(!session()->has('transaction_success'))
            <div class="grid grid-cols-4 gap-4">
                <!-- Stocks Table -->
                <div class="col-span-3">
                    <h2 class="text-2xl font-bold mb-4">Stocks Data</h2>
                    <table class="min-w-full bg-white">
                        <thead>
                        <tr>
                            <th class="py-2 px-4 bg-gray-200">Stock</th>
                            <th class="py-2 px-4 bg-gray-200">Price</th>
                            <th class="py-2 px-4 bg-gray-200">Change</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($stocks as $stock)
                            <tr class="cursor-pointer hover:bg-gray-100" wire:click="selectStock({{ $stock->id }})" wire:key="{{$stock->id}}">
                                <td class="py-2 px-4 border">{{ $stock->stock_name }}</td>
                                <td class="py-2 px-4 border">${{ $stock->current_price }}</td>
                                <td class="py-2 px-4 border">{{ number_format((($stock->current_price - $stock->low_price) / $stock->low_price) * 100, 2) }}%</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
{{--                        {{ $stocks->links() }}--}}
                    </div>
                </div>

                <!-- Buy/Sell Card -->
                <div class="col-span-1">
                    <h2 class="text-2xl font-bold mb-4">Buy/Sell</h2>
                    <div class="bg-white p-4 border rounded shadow">
                        @if ($loading)
                            <p>Loading....</p>
                        @elseif ($selectedStock)
                            <form wire:submit.prevent="submitTransaction">
                                <h3 class="text-xl font-semibold mb-2">{{ $selectedStock['stock_name'] }}</h3>
                                <p>Price: ${{ $selectedStock['current_price'] }}</p>
                                <label>
                                    Quantity:
                                    <input type="number" wire:model="quantity" name="quantity" min="1" required>
                                </label><br>
                                <label>
                                    <input type="radio" wire:model="transactionType" name="transaction_type" value="buy" required> Buy
                                </label>
                                <label>
                                    <input type="radio" wire:model="transactionType" name="transaction_type" value="sell" required> Sell
                                </label><br>
                                <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
                            </form>
                        @else
                            <p>Select a stock to see details</p>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div>Processing.....</div>
        @endif
    </div>

