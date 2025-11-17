            </main>
        </div>
    </div>
    
    <script src="<?php echo asset('js/admin.js'); ?>"></script>
    <script>
    function toggleSidebar() {
        document.querySelector('.admin-sidebar').classList.toggle('active');
    }
    
    function confirmDelete(message = 'Bu kaydı silmek istediğinizden emin misiniz?') {
        return confirm(message);
    }
    </script>
</body>
</html>

