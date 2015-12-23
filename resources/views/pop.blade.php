
<html>
    <head>
        <!-- Magnific Popup core CSS file -->
        <link rel="stylesheet" href="css/magnific-popup.css">
    </head>
<body>
<style>
    #open-popup {padding:20px}
    .white-popup {
        position: relative;
        background: #FFF;
        padding: 40px;
        width: auto;
        max-width: 200px;
        margin: 20px auto;
        text-align: center;
    }
</style>

<button id="open-popup">Open popup</button>

<div id="my-popup" class="mfp-hide white-popup">
    Inline popup
</div>
</body>
</html>

    <script src="js/jquery-1.11.3.min.js"></script>
    <!-- jQuery 1.7.2+ or Zepto.js 1.0+ -->
    <script src="js/jquery.magnific-popup.min.js"></script>

    <!-- Magnific Popup core JS file -->
    <script src="js/jquery.magnific-popup.js"></script>
<script>
    $('#open-popup').magnificPopup({
        items: [
            {
                src: '/uploads/6dbbf28165f812256c4e6badb04cb90f.jpg',
                title: 'Peter & Paul fortress in SPB'
            },
            {
                src: 'http://vimeo.com/123123',
                type: 'iframe' // this overrides default type
            },
            {
                src: $('<div class="white-popup">Dynamically created element</div>'), // Dynamically created element
                type: 'inline'
            },
            {
                src: '<div class="white-popup">Popup from HTML string</div>', // HTML string
                type: 'inline'
            },
            {
                src: '#my-popup', // CSS selector of an element on page that should be used as a popup
                type: 'inline'
            }
        ],
        gallery: {
            enabled: true
        },
        type: 'image' // this is a default type
    });
</script>

</body>

</html>
