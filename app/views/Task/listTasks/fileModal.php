<div class="modal fade" id="file_modal" role="dialog" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" id="form-file">
                <div class="modal-header">
                    <button type="button" class="btn btn-danger close" data-dismiss="modal">
                        &times;
                    </button>
                    
                    <h4 class="modal-title">
                        Anexar arquivo
                    </h4>
                </div>
            
                <div class="row" style="margin-top:20px">
                    <div class="col-sm-1"></div>
                    <label class="control-label col-sm-2" for="files">
                        Arquivos <strong>*</strong>
                    </label>
                    
                    <div class="col-sm-8">
                        <input type="file" class="form-control" id="files" name="files[]" required multiple/>
                    </div>
                </div>
                
                <div class="row" style="margin-top:20px">
                    <div class="col-sm-7"></div>
                    <div class="col-sm-5">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            Cancelar
                        </button>
                        
                        <button type="submit" class="btn btn-success">
                            Confirmar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>