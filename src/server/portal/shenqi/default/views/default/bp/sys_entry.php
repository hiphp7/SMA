<?php $this->load->view('admin/common/header')?>
<?php $this->load->view('admin/common/top_bp')?>
<?php $this->load->view('admin/common/left')?>

<?php $this->load->view(isset($tpl) && $tpl ? $tpl : 'admin/main'); ?>

<?php $this->load->view('admin/common/footer')?>
