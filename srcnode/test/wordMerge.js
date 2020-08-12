var words = require('./words.json');
var wordsNew = require('./verifyWord.json');
var fs = require('fs');
var util = require('util');
utils.writeLog(Object.keys(words).length);
utils.writeLog(Object.keys(wordsNew).length);
for (var word in wordsNew) {
    // da ton tai
    if (word in words) {  
        Array.prototype.push.apply(words[word]["false"], wordsNew[word]["false"]);      
    } else {
        words[word] = wordsNew[word];
    }
    words[word]["false"]= removeDuplicates(words[word]["false"])
}
fs.writeFileSync('./wordsFinal.json',  JSON.stringify(words) , 'utf-8'); 
utils.writeLog(Object.keys(words).length);
function removeDuplicates(arr){
    let unique_array = []
    for(let i = 0;i < arr.length; i++){
        if(unique_array.indexOf(arr[i]) == -1){
            unique_array.push(arr[i])
        }
    }
    return unique_array
}
