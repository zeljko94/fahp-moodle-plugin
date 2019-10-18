function MatricaCell(x,y,val){
    let self = this;
    self.x = x;
    self.y = y;
    self.val = val;


    self.cell = document.createElement("td");
    self.cell.style.padding = "20px";
    self.cell.style.border = "1px solid black";
    self.cell.id = "MatricaCell" + self.x + "_" + self.y;
    self.cell.innerHTML = self.val;
}
function Matrica(){
    let self = this;

    self.json = {};

    for(var i=0; i<kriteriji.length+1; i++){
        self.json[i] = {};
    }

    self.toJSON = function()
    {
        for(var i=0; i<kriteriji.length; i++)
        {
            for(var j=0; j<kriteriji.length; j++)
            {
                self.json[i][j] = self.json[i][j].replace("+", "%2B");
            }
        }
        return JSON.stringify(self.json);
    }
}