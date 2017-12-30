<!-- PESSOAL-->
<!-- Actions bar -->
<div class="zdk-action-bar" 
     data-zdk-dialog="dlg_fournisseur" 
     data-zdk-datatable="table_fournisseurs">
    <button class="zdk-bt-add" type="button" title="Adicionar novo registro">Novo</button>
    <button class="zdk-bt-edit" data-zdk-noselection="Selecione um registro para editar" type="button" title="Alterar registro">Editar</button>
    <button class="zdk-bt-remove" type="button" title="Excluir registro" 
            data-zdk-noselection="Selecione um registro para excluir."
            data-zdk-confirm="Deseja remover o registro selecionado?:Sim:Não"
            data-zdk-action="fournisseurctrl:supprimer">Excluir</button>
    
     <!-- Number of rows per page -->
    <select class="zdk-select-rows" title="Registros por página">  
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="100">All</option>
    </select>
    
    
</div>
<!-- Tabela de pessoal -->
<div id="table_fournisseurs" class="zdk-datatable" title="Militares cadastrados" 
     data-zdk-action="fournisseurctrl:lister" 
      data-zdk-paginator="5" 
     data-zdk-columns='[
     {"field":"id", "headerText": "Nº"},
     {"field":"nom", "headerText": "Posto/Grad"},
     {"field":"adresse", "headerText": "Nome"},
     {"field":"code_postal", "headerText": "Nome de guerra"},
     {"field":"ville", "headerText": "SU"}]'>
</div>
<!--Form dialog-->
<div id="dlg_fournisseur" class="zdk-modal" title="Fournisseur">
    <form class="zdk-form"
          data-zdk-action="fournisseurctrl:enregistrer"
          data-zdk-datatable="table_fournisseurs">
        <label>Nº : </label>
        <input name="id" disabled type="text">
        
        <label>Posto/Grad : </label>
        <input name="nom" maxlength="50" required type="text">
        
        <label>Nome : </label>
        <input name="adresse" required type="text">
        
        <label>Nome de guerra : </label>
        <input name="code_postal" required type="text">
        
        <label>SU : </label>
        <input name="ville" maxlength="50" required type="text">
        
        <button class="zdk-bt-save zdk-close-dialog" type="submit">Salvar</button>
        <button class="zdk-bt-cancel zdk-close-dialog" type="button">Cancelar</button>
    </form>
</div>

<!-- Styles for the Datatable's columns --> 
<style>
    #table_fournisseurs tr > td:first-of-type, 
    #table_fournisseurs thead > th:first-of-type {
        text-align:center;
        width: 2px;
    }

    #table_fournisseurs tr > td:nth-of-type(2), 
    #table_fournisseurs thead > th:nth-of-type(2) {
         text-align:center;
        width:30px;
    }

    #table_fournisseurs tr > td:nth-of-type(3), 
    #table_fournisseurs thead > th:nth-of-type(3) {
        text-overflow:ellipsis;
         width: 110px;
    }

     #table_fournisseurs tr > td:nth-of-type(4), 
    #table_fournisseurs thead > th:nth-of-type(4) {
        text-align:center;
         width: 10px;
    }

    #table_fournisseurs tr > td:last-of-type, 
    #table_fournisseurs thead > th:last-of-type {
         text-align:center;
         width: 10px;


    }
</style>