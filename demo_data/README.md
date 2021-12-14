# How to load demo data to our schema
0. Go to PhpMyAdmin, click 'Import', choose ./Jaunty_Jalopies/sql/team071_p2_schema.sql, click 'Go'
1. Download [miniconda](https://docs.conda.io/en/latest/miniconda.html) 
2. Run `conda create --name load_demo` in terminal
3. After conda env is created, run `conda activate load_demo` and you should see the env is switched to load_demo now
4. Change your dir in terminal to ./Jaunty_Jalopies/demo_data and run `conda env update`. NOTE: if your host and port 
   setting in load_user_data.py is different from mine, you'll have to update these two manually (same as other *_data.py), 
   otherwise you can leave as it is
5. Run `python load_all.py`. Now data should have been loaded to DB server in PhpMyAdmin