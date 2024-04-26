<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Twilight Shop</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            background-color: #f8f8f8;
        }

        h1 {
            color: #333;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        p {
            margin-top: 20px;
            font-weight: bold;
            text-align: right;
        }

        .invoice-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* Ajoutez ici d'autres styles CSS si nécessaire */

    </style>
</head>
<body>
    <div class="invoice-info">
        <h2>Twilight Shop</h2>
        <p>123 Main Street, Cityville</p>
        <p>Email: info@twilightshop.com</p>
        <p>Phone: (123) 456-7890</p>
        <p>Date: {{ now()->format('Y-m-d') }}</p>
    </div>

    <h1>Invoice</h1>

    <table>
        <thead>
            <tr>
                <th>Book</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @isset($cart)
                @forelse($cart as $book)
                    <tr>
                        <td>{{ $book['name'] }}</td>
                        <td>{{ $book['quantity'] }}</td>
                        <td>{{ $book['price'] }}</td>
                        <td>{{ $book['quantity'] * $book['price'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No items in the cart</td>
                    </tr>
                @endforelse
            @else
                <tr>
                    <td colspan="4">No items in the cart</td>
                </tr>
            @endisset
        </tbody>
    </table>
    @isset($totalAmount)
        <p>Total Amount: {{ $totalAmount }}</p>
    @endisset

    {{-- <p>Total Amount: {{ $totalAmount }}</p> --}}

    <!-- Ajoutez ici d'autres informations de facture si nécessaire -->

</body>
</html>
