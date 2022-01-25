Differenziazioni branches. 

## 1.x
Il branch `1.x` è il branch master per la versione 1 del pacchetto.
Questa versione supporta laravel >=5.6 e laravel <= 6.x

Per eventuali modifiche/bug fix e release si segue la metodologia git flow (master <- releases <- develop <- feature)

### Aggiunta di una nuova feature

1. creare un nuovo branch dal branch develop-1.x con la seguente nomenclatura: `feature/1.x/nome-feature`
2. una volta concluso lo sviluppo, mergiare su **develop-1.x**

### Creazione di una Release
1. Una volta che il branch develop-1.x è pronto per una release, 
creare un nuovo branch partendo da esso con la seguente nomenclatura `release/v1.x.y` (Es: release/v1.2.3).
2. Una volta che le eventuali modifiche sono completate, mergiare il branch release creato al branch **1.x**.

### Pubblicazione su packagist
Dal branch 1.x creare una nuova release con tag v1.x.y (Es: `v1.2.3`) e in automatico verrà pubblicata la nuova versione.

## 2.x
Per la versione 2.x, che supporta laravel dalla versione 7 in poi, seguire i stessi passaggi per la versione 1.x
sostituendo:
- `1.x` ➡️ `2.x`
- `develop-1.x` ➡️ `develop-2.x`
- `feature/1.x/nome-feature` ➡️ `feature/2.x/nome-feature`
- `hotfix/1.x/nome-hotfix` ➡️ `hotfix/2.x/nome-hotfix`
- `release/v1.x.y` ➡️ `release/v2.x.y` 
