

    @for($i=2;$i<count($files);$i++)
        <form method="post" class="form_Services" action="apply/upload" enctype="multipart/form-data" name="form">

            <input type="file" name="myfile">

                    <img src="/uploads/{{$files[$i]}}" id="img" name="{{$files[$i]}}" width=5%" height="5%">
                    <input type="hidden" value="{{csrf_token()}}" name="_token">
                    <input name="submit" type="submit" value="upload">
        </form>

    @endfor

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>

    <script>
        $(document).ready(function() {
            $(".form_Services").submit(function(){
                var oldimage = $(this).find("img").attr("name");

                $(this).ajaxSubmit({
                    data: {
                        oldimage:oldimage
                    },
                    resetForm: true,
                    uploadProgress: function(event, position, total, percentComplete) {

                    },
                    dataType:"json",
                    success: function() {

                        console.log('success: ');
                    },
                    error:function() {
                        console.log('error: ');
                    }
                });

                return false;

            });
        });






</script>
