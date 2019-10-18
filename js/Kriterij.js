
function Kriterij(redniBroj){
    let self = this;

    self.redniBroj = redniBroj;

    self.redak = document.createElement("tr");
    self.redak.id = "KriterijRedak" + self.redniBroj;

    self.inputField = document.createElement("input");
    self.inputField.type = "text";
    self.inputField.id = "KriterijInputField" + self.redniBroj;

    self.btnUkloni = document.createElement("div");
    self.btnUkloni.className = "btn btn-primary";
    self.btnUkloni.innerHTML = "Ukloni kriterij";

    self.inputFieldCell = document.createElement("td");
    self.inputFieldCell.appendChild(self.inputField);

    self.btnUkloniCell = document.createElement("td");
    self.btnUkloniCell.appendChild(self.btnUkloni);

    self.redak.appendChild(self.inputFieldCell);
    self.redak.appendChild(self.btnUkloniCell);
}