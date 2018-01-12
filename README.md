# Marrick Medical PHP Test
## Instructions
Given the Database files in the `src` directory, tell us how you would use the class `QueryLocalDBRequest` to
join two tables together.

Return all of the patients that are linked to the Law Firm with the id of 1.

Join tables 
- patients
- links_patients_to_law_firms
- law_firms

On
- links_patients_to_law_firms

## Tables Structure:
### Patients
patientID | name
---|-----

### Law Firms
lawFirmID | name
---|-----

### Links Patients to Law FIrms
linkID | patientID | lawFirmID
---|-----|----

## Submit

Use the index.php file to write your solution.

When you are done, submit your code by zipping the project and sending it in an email as an attachment.
