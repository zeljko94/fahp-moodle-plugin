function TrokutniBrojSelect()
{
    let self = this;
    self.select = document.createElement("select");


    for(var i=0; i<Object.keys(TrokutniFuzzyBrojEnumDict).length; i++)
    {
        var option = document.createElement("option");
        option.value = i;
        option.innerHTML = TrokutniFuzzyBrojEnumDict[i].toString;
        self.select.appendChild(option);
    }
}