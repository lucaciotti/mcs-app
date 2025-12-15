<x-wire-elements-pro::bootstrap.modal on-submit="save" :content-padding="false">
    <x-slot name="title">
        <h4 class="modal-title">{{ $title }}</h4>
    </x-slot>

    <!-- No padding will be applied because the component attribute "content-padding" is set to false -->
    <div class="modal-body">

        <div id="result"></div>
        <div id="reader">
            
        </div>

        <!-- Include the HTML5-QRCode Library -->
        
        <script>
            // Initialize the HTML5 QR Code Scanner
            let html5QRCodeScanner = new Html5QrcodeScanner(
                // Target element with the ID "reader" and configure settings
                "reader", {
                    fps: 10, // Frames per second for scanning
                    qrbox: {
                        width: 200, // Width of the scanning box
                        height: 200, // Height of the scanning box
                    },
                }
            );
        
            // Function executed when the scanner successfully reads a QR Code
            function onScanSuccess(decodedText, decodedResult) {
                // Redirect to the scanned QR Code link
                // window.location.href = decodedText;
                console.log(decodedText);
                document.getElementById('result').innerHtml = decodedText;
                // Clear the scanner area after the action is performed
                html5QRCodeScanner.clear();
            }
        
            // Render the QR Code scanner
            // html5QRCodeScanner.applyVideoConstraints({facingMode: "environment"}).then(html5QRCodeScanner.render(onScanSuccess));
            setTimeout(function () {
            html5QRCodeScanner.applyVideoConstraints({
            facingMode: "environment"
            // advanced: [{ zoom: 2.0 }],
            });
            }, 2000);
            html5QRCodeScanner.render(onScanSuccess);
        </script>

    </div>

    <x-slot name="buttons">
        <button type="button" class="btn btn-default float-left" wire:click="$emit('modal.close')">Cancella</button>
        <button type="submit" class="btn btn-primary">Salva</button>
    </x-slot>
</x-wire-elements-pro::bootstrap.modal>
