window.validateForm = (errFields, fields) => {
    Object.keys(fields).map(field => {
        if (errFields.hasOwnProperty(field)) {
            fields[field] = errFields[field][0];
        } else {
            fields[field] = null;
        }
    });
};
