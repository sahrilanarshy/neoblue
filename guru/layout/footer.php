<footer class="footer">
    <div class="container-fluid d-flex justify-content-between">
        <div class="copyright">
            Copyright © 2025</i>
            <a href="#">NeoBlue</a>
        </div>
        <div>
            Version 1.7 - Last update :
            <a target="_blank" href="#">2025-10-2</a>
        </div>
    </div>
</footer>
</div>

<!-- End Custom template -->
</div>
<!--   Core JS Files   -->
<script src="../assets/admin/js/core/jquery-3.7.1.min.js"></script>
<script src="../assets/admin/js/core/popper.min.js"></script>
<script src="../assets/admin/js/core/bootstrap.min.js"></script>

<!-- jQuery Scrollbar -->
<script src="../assets/admin/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

<!-- Chart JS -->
<script src="../assets/admin/js/plugin/chart.js/chart.min.js"></script>

<!-- jQuery Sparkline -->
<script src="../assets/admin/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

<!-- Chart Circle -->
<script src="../assets/admin/js/plugin/chart-circle/circles.min.js"></script>

<!-- Datatables -->
<script src="../assets/admin/js/plugin/datatables/datatables.min.js"></script>

<!-- jQuery Vector Maps -->
<script src="../assets/admin/js/plugin/jsvectormap/jsvectormap.min.js"></script>
<script src="../assets/admin/js/plugin/jsvectormap/world.js"></script>

<!-- Sweet Alert -->
<script src="../assets/admin/js/plugin/sweetalert/sweetalert.min.js"></script>

<!-- Kaiadmin JS -->
<script src="../assets/admin/js/kaiadmin.min.js"></script>

<!-- Kaiadmin DEMO methods, don't include it in your project! -->
<script src="../assets/admin/js/setting-demo.js"></script>
<script src="../assets/admin/js/demo.js"></script>
<script>
    $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#177dff",
        fillColor: "rgba(23, 125, 255, 0.14)",
    });

    $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#f3545d",
        fillColor: "rgba(243, 84, 93, .14)",
    });

    $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#ffa534",
        fillColor: "rgba(255, 165, 52, .14)",
    });

    $(document).ready(function() {
        $("#basic-datatables").DataTable({});

        $("#multi-filter-select").DataTable({
            pageLength: 5,
            initComplete: function() {
                this.api()
                    .columns()
                    .every(function() {
                        var column = this;
                        var select = $(
                                '<select class="form-select"><option value=""></option></select>'
                            )
                            .appendTo($(column.footer()).empty())
                            .on("change", function() {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                column
                                    .search(val ? "^" + val + "$" : "", true, false)
                                    .draw();
                            });

                        column
                            .data()
                            .unique()
                            .sort()
                            .each(function(d, j) {
                                select.append(
                                    '<option value="' + d + '">' + d + "</option>"
                                );
                            });
                    });
            },
        });

        // Add Row
        $("#add-row").DataTable({
            pageLength: 5,
        });

        var action =
            '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

        $("#addRowButton").click(function() {
            $("#add-row")
                .dataTable()
                .fnAddData([
                    $("#addName").val(),
                    $("#addPosition").val(),
                    $("#addOffice").val(),
                    action,
                ]);
            $("#addRowModal").modal("hide");
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Mengambil URL lengkap dari halaman yang sedang aktif
        var currentUrl = window.location.href;

        // Mencari setiap link (<a>) di dalam menu sidebar
        $('.sidebar .nav-item a').each(function() {

            // Memeriksa apakah URL link ini sama dengan URL halaman saat ini
            if (this.href === currentUrl) {

                // Jika cocok, tambahkan kelas 'active' ke elemen <li> terdekat
                $(this).closest('.nav-item').addClass('active');

                // Hentikan loop setelah menemukan link yang cocok (opsional, untuk efisiensi)
                return false;
            }
        });
    });
</script>
