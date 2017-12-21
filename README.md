# Marrick Medical PHP Test

Given the Database files in the `src` directory, tell us how you would use the class `QueryLocalDBRequest` to
join to tables together.

Return all of the patients that are linked to the Law Firm with the id of 1.

Join tables 
- patients
- links_patients_to_law_firms
- law_firms

On
- links_patients_to_law_firms

## Tables Structure:
### Patients
id | name
---|-----

### Law Firms
id | name
---|-----

### Links Patients to Law FIrms
id | patient_id | law_firm_id
---|-----|----