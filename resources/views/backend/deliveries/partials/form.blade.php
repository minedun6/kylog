<div class="form-body">
    <div class="tab-content">
        <div class="tab-pane active" id="general">
            @include ('backend.deliveries.partials.form-components')
        </div>
        <div class="tab-pane" id="files">
            Files
        </div>
    </div>
</div>
<div class="form-actions right">
    <button type="submit" class="btn blue"
            data-disable-with="<i class='fa fa-refresh fa-spin fa-fw'></i> Sauvegarde...">
        <span class="fa fa-save"></span>
        Save
    </button>
    <button type="reset" value="Reset" class="btn default">Cancel</button>
</div>
