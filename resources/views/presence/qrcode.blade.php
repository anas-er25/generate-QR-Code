<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR code</title>
  <link rel="icon" type="image/x-icon" href="/images/favicon-32x32.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  {{-- <link rel="stylesheet" href="css/style.css"> --}}
  <style>
    body {
    background-color:hsl(212, 45%, 89%);
}

p{
    color:hsl(220, 15%, 55%);
}

div{
    margin-top: 10px;
    display: block;
    margin-left: auto; 
    margin-right: auto;
    padding-top: 15px;
    padding-left: 15px;
    padding-right: 15px;
    padding-bottom: 15px; 
}
  </style>
</head>
<body>
    
    {{-- Afficher le QR Code --}}
    <div class="container mt-3">
        <h1 class="text-center">Scanner le Code QR</h1>
        <h4 class="text-center">
            Bienvenue {{ auth()->user()->name }}, merci de enregistrer votre date d'entrée
        </h4>
        <div class="row-cols-sm-3">
        <div class="card mb-3">
            <div class="card-img-top border-top">{{ $qrCode }}</div>
            <div class="card-body">
              <div class="card-title text-center">
                <form id="scanForm" action="/checkin" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" id="userId" name="userId" value="{{ $qrData['user_id'] }}">
                    <button type="submit" class="btn btn-primary">Enregistrer l'entrer</button>
                </form>
              </div>
              </div>
          </div>
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
    <script>
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector("#interactive"),
                constraints: {
                    width: 480,
                    height: 320,
                    facingMode: "environment"
                }
            },
            decoder: {
                readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader", "code_39_vin_reader", "codabar_reader", "upc_reader", "upc_e_reader", "i2of5_reader"]
            }
        }, function (err) {
            if (err) {
                console.error(err);
                return;
            }
            Quagga.start();
        });

        document.getElementById('userId').value = userData.id;
        Quagga.onDetected(function (result) {

            let userData = JSON.parse(result.code);


            document.getElementById('scanForm').submit();
        });
    </script>
</body>
</html>
