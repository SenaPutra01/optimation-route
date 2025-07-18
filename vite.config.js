import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                // theme css
                "resources/assets/vendors/mdi/css/materialdesignicons.min.css",
                "resources/assets/vendors/css/vendor.bundle.base.css",
                "resources/assets/css/style.css",
                "resources/assets/vendors/jvectormap/jquery-jvectormap.css",
                "resources/assets/vendors/flag-icon-css/css/flag-icon.min.css",

                //theme js
                "resources/assets/vendors/js/vendor.bundle.base.js",
                "resources/assets/js/off-canvas.js",
                "resources/assets/js/hoverable-collapse.js",
                "resources/assets/js/misc.js",
                "resources/assets/js/settings.js",
                "resources/assets/js/todolist.js",

                "resources/assets/vendors/chart.js/Chart.min.js",
                "resources/assets/vendors/progressbar.js/progressbar.min.js",
                "resources/assets/vendors/jvectormap/jquery-jvectormap.min.js",
                "resources/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js",
                "resources/assets/js/off-canvas.js",
                "resources/assets/js/hoverable-collapse.js",
                "resources/assets/js/misc.js",
                "resources/assets/js/settings.js",
                "resources/assets/js/dashboard.js",
                // "resources/",

                //              <script src="assets/vendors/chart.js/Chart.min.js"></script>
                // <script src="assets/vendors/progressbar.js/progressbar.min.js"></script>
                // <script src="assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
                // <script src="assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
                // <script src="assets/vendors/owl-carousel-2/owl.carousel.min.js"></script>
                // <!-- End plugin js for this page -->
                // <!-- inject:js -->
                // <script src="assets/js/off-canvas.js"></script>
                // <script src="assets/js/hoverable-collapse.js"></script>
                // <script src="assets/js/misc.js"></script>
                // <script src="assets/js/settings.js"></script>
                // <script src="assets/js/todolist.js"></script>
                // <!-- endinject -->
                // <!-- Custom js for this page -->
                // <script src="assets/js/dashboard.js"></script>
            ],
            refresh: true,
        }),
    ],
});
