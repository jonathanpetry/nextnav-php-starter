            </main>
        </div>
        </div>
    <div class="toast-region" aria-live="polite" aria-atomic="false" data-toast-region></div>
    <?php if (!empty($loadTabulator)): ?>
    <script src="<?= APP_URL ?>/assets/vendor/tabulator/tabulator.min.js?v=5.5.4"></script>
    <?php endif; ?>
    <script src="<?= APP_URL ?>/assets/app.js?v=<?= filemtime(PUBLIC_PATH . '/assets/app.js') ?>"></script>
</body>
</html>
