<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Listing - soipattaya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }

        h2 {
            color: #cc0000;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 700px;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        label {
            font-weight: bold;
            margin-top: 15px;
            display: block;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        textarea {
            min-height: 250px;
        }

        button {
            background: #cc0000;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #a30000;
        }

        .qr-box {
            text-align: center;
            margin-top: 20px;
        }

        .copyable {
            background: #eee;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            word-break: break-word;
        }

        .copyable:active {
            background: #ccc;
        }

        .errors {
            color: red;
            background: #ffe6e6;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .center-button {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
    </style>

    <script>
        function copyWallet() {
            const text = document.getElementById('wallet-address').innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert('Copied wallet address to clipboard');
            });
        }
    </script>
</head>
<body>

    <h2 style="text-align:center;">📝 Create New Listing</h2>

    @if ($errors->any())
        <div class="errors">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form method="POST" action="/post">
        @csrf
        <input type="hidden" name="reference" id="reference" value="{{ old('reference') }}">

        <label>📍 Google Maps Coordinates:</label>
        <input name="coordinates" placeholder="13.736717,100.523186" value="{{ old('coordinates') }}" >

        <label>🏢 Building Name:</label>
        <input name="building_name" value="{{ old('building_name') }}" >

        <label>🪜 Floor:</label>
        <input name="floor" value="{{ old('floor') }}" >

        <label>📐 SQM:</label>
        <input name="sqm" type="number" value="{{ old('sqm') }}" >

        <label>💰 Cost (Baht):</label>
        <input name="cost" type="number" value="{{ old('cost') }}" >

        <label>📝 Description (you can use emojis, new lines, etc.):</label>
        <textarea name="description" rows="5" >{{ old('description') }}</textarea>

        <label>▶️ YouTube Embed Link:</label>
        <input name="youtube_link" value="{{ old('youtube_link') }}" >

        <div class="qr-box">
            <strong><span style="color:green;">SOLANA</span></strong>
            <p><strong>Scan to send <span style="color:green;">1 USDC</span> to:</strong></p>
            <canvas id="qr"></canvas>
        </div>

        <div class="center-button">
            <button type="submit" id="submit-btn">✅ Submit Listing</button>
        </div>
    </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@solana/web3.js@1.78.2/lib/index.iife.min.js"></script>

    <script>
    window.onload = function generateQr() {
        const recipient = new solanaWeb3.PublicKey('3BVC8axBgNE8sopUMqYq5ros5szmcxrYmXPgJEmGnZPy');
        const usdcMint = new solanaWeb3.PublicKey('EPjFWdd5AufqSSqeM2qN1xzybapC8G4wEGGkZwyTDt1v');
        const reference = solanaWeb3.Keypair.generate().publicKey;
        const amount = 1;

        // More explicit payment URL
        const url = `solana:${recipient}?amount=${amount}` +
                    `&spl-token=${usdcMint}` +
                    `&reference=${reference}` +
                    `&label=SoiPattaya` +
                    `&message=Listing_Payment_${reference.toBase58().slice(0,8)}`;
        
        new QRious({
            element: document.getElementById('qr'),
            value: url,
            size: 250
        });

        document.getElementById('reference').value = reference.toBase58();
        };
    </script>

</body>
</html>