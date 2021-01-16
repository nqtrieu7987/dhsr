export default {
    methods: {
        addItemToObject (obj, key, value, index) {
            var temp = {};
            var i = 0;
            for (var prop in obj) {
                if (obj.hasOwnProperty(prop)) {
                    if (i === index && key && value) {
                        temp[key] = value;
                    }
                    temp[prop] = obj[prop];
                    i++;
                }
            }
            if (!index && key && value) {
                temp[key] = value;
            }
            return temp;
        },
        changeStyleObject(data) {
            var arr = [];
            for (const [key, value] of Object.entries(data)) {
                arr.push({
                    code: key,
                    label: value
                })
            }
            return arr;
        },
        getFileName(file) {
            if(!file || file === '' || file === null) return '';
            let arr = file.split("/");
            let name = arr[arr.length-1];
            if (name.length > 15) name = name.substr(0, 15)+'...';
            return name;
        },
    }
}
