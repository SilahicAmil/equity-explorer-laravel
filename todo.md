- Create Each Page Layout
    - Take the work I did in nextjs project and just copy it over. Or just recreate it. Wont take long

- Store stock data in REDIS for caching and faster fetching
- Each user I assume would have their own redis key filled with stock data info. Revalidate every so often (depends on when scripts are ran).
- Add color pallette to tailwind config
- Create history view of stock data? (maybe)
- Add leaderboard in portfolio page? Ranked based off of amount of money in account or invested?
- Create scripts to run every 4-8-12-24 (havent decided yet) hours automatically for stock data. From polygon API.
    
- IN THE FUTURE: Add a crypto side. TBD (I hate crypto)

Done:

- Add stock_transactions table to store bought and sold.
- Store user stock data in a db. Like time bought, amount, stock type, stock symbol, percentage change and etc