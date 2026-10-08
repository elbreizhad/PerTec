-- Date de la première échéance du prêt (différé éventuel). Vide = mois suivant l'achat.
ALTER TABLE properties ADD COLUMN loan_start_date DATE NULL
