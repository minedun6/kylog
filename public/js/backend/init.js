let initComponents = function () {
    let t = function () {
        jQuery().datepicker && $(".date-picker").datepicker({
            language: 'fr',
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
        })
    };
    let a = function () {
        let select2 = $(".select2");
        jQuery().select2 && select2.select2({
            placeholder: $(this).data('placeholder'),
            theme: "bootstrap",
            allowClear: true,
            width: null
        });
    };
    let o = function () {
        jQuery().daterangepicker && $(".date-range").daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: "right",
            },
            function (start, end) {
                $('.date-range span').html(start.format('LL') + ' - ' + end.format('LL'));
            }
        );
        jQuery().daterangepicker && $("#reception_date").daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: "right",
            },
            function (start, end) {
                $('#reception_date span').html(start.format('LL') + ' - ' + end.format('LL'));
            }
        );
        jQuery().daterangepicker && $("#delivery_order_date").daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: "right",
            },
            function (start, end) {
                $('#delivery_order_date span').html(start.format('LL') + ' - ' + end.format('LL'));
            }
        );
    };
    let c = function () {
        if (typeof(tinyMCE) != "undefined") {
            var editor_config = {
                path_absolute: "/",
                selector: "textarea.editor",
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                relative_urls: false,
                file_browser_callback: function (field_name, url, type, win) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

                    var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                    if (type == 'image') {
                        cmsURL = cmsURL + "&type=Images";
                    } else {
                        cmsURL = cmsURL + "&type=Files";
                    }

                    tinyMCE.activeEditor.windowManager.open({
                        file: cmsURL,
                        title: 'Filemanager',
                        width: x * 0.8,
                        height: y * 0.8,
                        resizable: "yes",
                        close_previous: "no"
                    });
                }
            };

            tinymce.init(editor_config);
        }
    };
    return {
        init: function () {
            t(), a(), o(), c()
        }
    }
}();

jQuery(document).ready(function () {
    initComponents.init()
});
