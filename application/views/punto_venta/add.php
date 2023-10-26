<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Añadir Token</h3>
            </div>
            <?php echo form_open('token/add'); ?>
                <div class="box-body">
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <label for="token_delegado" class="control-label"><span class="text-danger">*</span>Token Delegado</label>
                            <div class="form-group">
                                <input type="text" name="token_delegado" value="<?php echo $this->input->post('token_delegado'); ?>" class="form-control" id="token_delegado" required autofocus autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="token_fechadesde" class="control-label"><span class="text-danger">*</span>Desde</label>
                            <div class="form-group">
                                <input type="date" name="token_fechadesde" value="<?php echo ($this->input->post('token_fechadesde') ? $this->input->post('token_fechadesde') : date('Y-m-d')); ?>" class="form-control" id="token_fechadesde" required />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="token_fechahasta" class="control-label"><span class="text-danger">*</span>Hasta</label>
                            <div class="form-group">
                                <input type="date" name="token_fechahasta" value="<?php echo ($this->input->post('token_fechahasta') ? $this->input->post('token_fechahasta') : date('Y-m-d')); ?>" class="form-control" id="token_fechahasta" required />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Guardar
                    </button>
                    <a href="<?php echo site_url('token'); ?>" class="btn btn-danger">
                        <i class="fa fa-times"></i> Cancelar</a>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>