<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Viewer</title>
    <script src="{{ asset('pdfjs/build/pdf.mjs') }}" type="module"></script>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        #pdf-viewer {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
    <iframe id="pdf-viewer" src="{{ asset('pdfjs/web/viewer.html') }}?file={{ route('dokumen.view', $id) }}"></iframe>
    
    <script>
        document.getElementById('pdf-viewer').onload = function() {
            console.log('iframe loaded');
            this.contentWindow.onerror = function(message, source, lineno, colno, error) {
                console.error('PDF.js error:', message, error);
                alert('Error loading PDF: ' + message);
            };
        };

        console.log("PDF URL: {{ route('dokumen.view', $id) }}");
        console.log("Viewer URL: {{ asset('pdfjs/web/viewer.html') }}");
    </script>
</body>
</html>