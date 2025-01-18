</div>

<script src="<?php echo BASE_URL; ?>assets/js/Libraries/bootstrap.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/Libraries/jquery-3.7.1.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/Libraries/notyf.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/Libraries/sweetalert2.all.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/Libraries/dataTables.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/Libraries/select2.min.js"></script>
<script>
    const BASE_URL = "<?php echo BASE_URL; ?>";
</script>
<script src="<?php echo BASE_URL; ?>assets/js/Pages/user.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/Pages/employee.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/Pages/evidence.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/Modules/sidebar.js"></script>
<script type="text/javascript">
    var idEmpleado = <?php echo isset($_SESSION['id_empleado']) ? $_SESSION['id_empleado'] : 'null'; ?>;
    var idRol = <?php echo isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : 'null'; ?>;
    var nombreCompletoUsuario = "<?php echo $_SESSION['nombre_completo'] ?? 'Usuario'; ?>";
</script>

</body>

</html>