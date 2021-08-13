# Pipeline

GIT deployment pipeline application written in PHP.

Opinionated deployments roughly using the gitflow process but with allowances for
some customisation.


## Notes
1. `VERSION` file is updated during each deployment to the next release number.
2. Merge conflicts are never automatically resolved, scripts will stop if conflicts
   are encountered.
3. Limited OOP usage, only where it helps the flow of the application.
