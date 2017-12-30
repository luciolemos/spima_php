<!-- PATRIMÔNIO-->
<!-- Actions bar -->
<div class="zdk-action-bar" 
     data-zdk-dialog="demo-crud-dlg" 
     data-zdk-datatable="demo-crud-table">
    <!-- Action buttons -->
    <button class="zdk-bt-add" type="button" title="Adicionar novo registro">Novo</button>
    <button class="zdk-bt-edit" data-zdk-noselection="Selecione um registro para editar" type="button" title="Alterar registro">Editar</button>
    <button class="zdk-bt-remove" type="button" title="Excluir registro"
            data-zdk-noselection="Selecione um registro para excluir."
            data-zdk-confirm="Deseja remover o registro selecionado?:Sim:Não"
            data-zdk-action="democrudctrl:remove">Excluir</button>
    
    <!-- Number of rows per page -->
    <select class="zdk-select-rows" title="Registros por página">  
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="100">All</option>
    </select>
    <!-- Search form -->
    <div class="zdk-filter-rows">
        <input title="search criteria..." data-zdk-action="democrudctrl:suggestions">
        <button class="zdk-bt-clear" title="Reset the search field content..."></button>
        <button class="zdk-bt-search" title="Search the products that match the criteria..."
                data-zdk-novalue="Please, type in a criteria first."></button>
    </div>
</div>
<!-- Datatable -->
<div id="demo-crud-table" class="zdk-datatable zdk-synchronize" title="Imóveis cadastrados" 
     data-zdk-action="democrudctrl:data"
     data-zdk-paginator="5" 
     data-zdk-columns='[
     {"field":"part_number", "headerText": "Nº", "sortable":true},
     {"field":"name", "headerText": "Endereço", "sortable":true},    
     {"field":"description", "headerText": "Tipo do PNR", "sortable":true, "tooltip":true},
     {"field":"price_money", "headerText": "Categoria do PNR", "sortable":true}]'>
</div>
<!--Form dialog-->
<div id="demo-crud-dlg" class="zdk-modal" title="Product" data-zdk-width="340px" data-zdk-confirm="Do you want to cancel your changes?:Yes:No">
    <form class='zdk-form' data-zdk-action="democrudctrl:save" data-zdk-datatable="demo-crud-table">
        <label>Nº</label>
        <input name="part_number" maxlength="10" required>
        
        <label>Endereço</label>
        <textarea name="name" rows="3" maxlength="200"></textarea>
        
        <label>Tipo de PNR</label>
        <select class="zdk-dropdown" name="description" title="Classiificação do PNR quanto ao tipo" required data-zdkerrmsg-required="Selecione o tipo de PNR.">
            
            <option value="" selected="selected">Selecione o tipo </option>
            <option value="Of Gen">Of Gen</option>
            <option value="Of Sup">Of Sup</option>
            <option value="Cap/Ten">Cap/Ten</option>
            <option value="ST/Sgt">ST/Sgt</option>
            <option value="Cb/Sd">Cb/Sd</option>         
        </select>
        
              
        <label>Categoria do PNR</label>
        <input name="price" required>
        <input name="id" type="hidden">
        
<!--        <label>Data de ocupação</label>
        <input type="date" name="fld_date"
               data-zdkerrmsg-date="Data de ocupação inválida.">-->
       
        <button class="zdk-bt-save zdk-close-dialog" type="submit">Salvar</button>
        <button class="zdk-bt-cancel zdk-close-dialog" type="button">Cancelar</button>
    </form>
</div>
<!-- Styles for the Datatable's columns --> 
<style>
    #demo-crud-table tr > td:first-of-type, 
    #demo-crud-table thead > th:first-of-type {
        text-align:center;
        width: 20px;
    }

    #demo-crud-table tr > td:nth-of-type(2), 
    #demo-crud-table thead > th:nth-of-type(2) {
        text-overflow:ellipsis;
        width:190px;
    }

    #demo-crud-table tr > td:nth-of-type(3), 
    #demo-crud-table thead > th:nth-of-type(3) {
        text-align:center;
         width: 20px;
    }

    #demo-crud-table tr > td:last-of-type, 
    #demo-crud-table thead > th:last-of-type {
         text-align:center;
        width:20px;
    }
</style>