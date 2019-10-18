
function Odnos(k1,k2,x,y){
    let self = this;

    self.k1 = k1;
    self.k2 = k2;
    self.x = x;
    self.y = y;
    self.isInverted = false;

    self.redak = document.createElement("tr");
    self.redak.id = "OdnosRedak" + x + "_" + y;

    self.k1Div = document.createElement("div");
    self.k2Div = document.createElement("div");

    self.k1Div.innerHTML = self.k1.inputField.value;
    self.k2Div.innerHTML = self.k2.inputField.value;

    self.seOdnosiDiv = document.createElement("div");
    self.seOdnosiDiv.innerHTML = "&nbsp;&nbsp;&nbsp; prema &nbsp;&nbsp;&nbsp;";

    self.btnZamijeni = document.createElement("div");
    self.btnZamijeni.innerHTML = "Zamijeni kriterije";
    self.btnZamijeni.className = "btn btn-primary";
    self.btnZamijeni.onclick = function(){
        self.isInverted = !self.isInverted;

        var indexiArr = self.redak.id.replace("OdnosRedak","");
        var indexi = indexiArr.split("_");

        var x = parseInt(indexi[0])+1;
        var y = parseInt(indexi[1])+1;

        var cell = document.getElementById("MatricaCell" + x + "_" + y);
        var cellInverz = document.getElementById("MatricaCell" + y + "_" + x);

        var pomk = self.k1Div.innerHTML;
        self.k1Div.innerHTML = self.k2Div.innerHTML;
        self.k2Div.innerHTML = pomk;

        var mpom = matrica.json[x][y];
        matrica.json[x][y] = matrica.json[y][x];
        matrica.json[y][x] = mpom;

        var pom = cell.innerHTML;
        cell.innerHTML = cellInverz.innerHTML;
        cellInverz.innerHTML = pom;
    }

    self.tfbSelect = new TrokutniBrojSelect();
    self.tfbSelect.select.onchange = function(){
        self.selection = self.tfbSelect.select.selectedIndex;

        var indexiArr = self.redak.id.replace("OdnosRedak", "");
        var indexi = indexiArr.split("_");

        var x = parseInt(indexi[0])+1;
        var y = parseInt(indexi[1])+1;

        var cell = document.getElementById("MatricaCell"+x+"_"+y);
        var inverzCell = document.getElementById("MatricaCell"+y+"_"+x);

        var tfb = TrokutniFuzzyBrojEnumDict[self.selection];
        var tfbInverz = tfb.vrijednost.inverz();

        if(self.isInverted == true){
            inverzCell.innerHTML = tfb.vrijednost.toString();
            cell.innerHTML = tfbInverz.toString();

            matrica.json[y][x] = tfb.vrijednost.toString();
            matrica.json[x][y] = tfbInverz.toString();
        }
        else{
            cell.innerHTML = tfb.vrijednost.toString();
            inverzCell.innerHTML = tfbInverz.toString();

            matrica.json[x][y] = tfb.vrijednost.toString();
            matrica.json[y][x] = tfbInverz.toString();
        }
    }

    if(self.selection != null)
        self.tfbSelect.select.selectedIndex = self.selection
    else
        self.tfbSelect.select.selectedIndex = 0

    self.k1DivCell = document.createElement("td");
    self.k1DivCell.appendChild(self.k1Div);

    self.k2DivCell = document.createElement("td");
    self.k2DivCell.appendChild(self.k2Div);

    self.seOdnosiDivCell = document.createElement("td");
    self.seOdnosiDivCell.appendChild(self.seOdnosiDiv);

    self.tfbSelectCell = document.createElement("td");
    self.tfbSelectCell.appendChild(self.tfbSelect.select);

    self.btnZamijeniCell = document.createElement("td");
    self.btnZamijeniCell.appendChild(self.btnZamijeni);

    self.redak.appendChild(self.k1DivCell);
    self.redak.appendChild(self.seOdnosiDivCell);
    self.redak.appendChild(self.k2DivCell);
    self.redak.appendChild(self.tfbSelectCell);
    self.redak.appendChild(self.btnZamijeniCell);

    self.equals = function(o){
        if((self.k1 == o.k1 && self.k2 == o.k2) || (self.k1 == o.k2 && self.k2 == o.k1))
            return true;
        return false;
    }
}