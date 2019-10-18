
var kriteriji = [];
var odnosi = [];
var odnosiBackup = [];
var matrica = null;
var brojKriterija = 0;
var kriterijiTable = document.getElementById("kriterijiTable");
var odnosiTable = document.getElementById("odnosiTable");
var matricaTable = document.getElementById("matricaTable");

function btnDodajKriterijOnClick(){
    var noviKriterij = new Kriterij(brojKriterija);
    kriteriji.push(noviKriterij);
    kriterijiTable.appendChild(noviKriterij.redak);
    brojKriterija++;
    updateOdnose();
    bindInputChangeEvents();

    noviKriterij.btnUkloni.onclick = function(){
        var indexKriterija = kriteriji.indexOf(noviKriterij);
        if(indexKriterija > -1){
            kriteriji.splice(indexKriterija, 1);
            kriterijiTable.removeChild(noviKriterij.redak);
            brojKriterija--;
            updateOdnose();
            bindInputChangeEvents();
        }
    }
}

function updateOdnose(){
    odnosiBackup = [];//odnosi;
    odnosi = [];

    removeTableRows(odnosiTable.id);
    for(var i=0; i<kriteriji.length; i++){
        for(var j=i+1; j<kriteriji.length; j++){
            var noviOdnos = new Odnos(kriteriji[i],kriteriji[j],kriteriji[i].redniBroj,kriteriji[j].redniBroj);
            for(var k=0; k<odnosiBackup.length; k++){
                if(noviOdnos.equals(odnosiBackup[k])){
                    noviOdnos.tfbSelect.select.selectedIndex = odnosiBackup[k].selection;
                }
            }
            odnosi.push(noviOdnos);
            odnosiTable.appendChild(noviOdnos.redak);
        }
    }
    updateMatricu();
}

function updateMatricu(){
    removeTableRows(matricaTable.id);

    matrica = new Matrica();


    if(kriteriji.length > 1){
        for(var i=0; i<kriteriji.length+1; i++){
            var redak = document.createElement("tr");
            redak.id = "MatricaRedak" + i;
            for(var j=0; j<kriteriji.length+1; j++){
                var matricaCell = null;

                if(i == j){
                    if(i==0){
                        matricaCell = new MatricaCell(i,j, "");
                    }
                    else{
                        var tfb = TrokutniFuzzyBrojEnumDict[0];
                        matricaCell = new MatricaCell(i,j,tfb.vrijednost.toString());
                    }
                }
                else{
                    if(i==0 && j>0){
                        matricaCell = new MatricaCell(i,j,kriteriji[j-1].inputField.value);
                    }
                    else if(j==0 && i>0){
                        matricaCell = new MatricaCell(i,j,kriteriji[i-1].inputField.value);
                    }
                    else{
                        var tfb = TrokutniFuzzyBrojEnumDict[0];
                        matricaCell = new MatricaCell(i,j,tfb.vrijednost.toString());
                    }
                }
                matrica.json[i][j] = matricaCell.val;
                redak.appendChild(matricaCell.cell);
            }
            matricaTable.appendChild(redak);
        }
    }
}

function removeTableRows(id){
    var table = document.getElementById(id);
    var rows = table.rows;
    for(var i=rows.length-1; i>=0; i--){
        table.deleteRow(i);
    }
}
function bindInputChangeEvents(){
    var inputFields = document.querySelectorAll('input[id^="KriterijInputField"]');
    for(var i=0; i<inputFields.length; i++){
        inputFields[i].oninput = function(){
            updateOdnose();
        }
        inputFields[i].onpropertychange = inputFields[i].oninput;
    }
}

function getOdnosByIndexes(x,y){
    for(var i=0; i<odnosi.length; i++){
        if(x == odnosi[i].x && y == odnosi[i].y)
            return odnosi[i];
    }
}
