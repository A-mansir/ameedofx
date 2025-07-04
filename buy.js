const express = require('express');
const session = require('express-session');
const mysql = require('mysql2/promise');
const fetch = require('node-fetch');
const bodyParser = require('body-parser');

const app = express();
const PORT = 3000;

// Database connection
const pool = mysql.createPool({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'your_db_name',
});

// Middleware
app.use(express.static('public'));
app.use(bodyParser.urlencoded({ extended: true }));
app.use(session({
  secret: 'ameedofx-secret',
  resave: false,
  saveUninitialized: false,
}));

// Middleware to protect routes
function authMiddleware(req, res, next) {
  if (!req.session.user_id) return res.redirect('/login');
  next();
}

// Serve Buy Crypto Page
app.get('/buy', authMiddleware, async (req, res) => {
  const coin_ids = 'bitcoin,ethereum,tether,binancecoin,solana,ripple,cardano,dogecoin';
  const response = await fetch(`https://api.coingecko.com/api/v3/simple/price?ids=${coin_ids}&vs_currencies=usd`);
  const prices = await response.json();

  let options = '';
  for (const coin in prices) {
    options += `<option value="${coin}">${coin.toUpperCase()} - $${prices[coin].usd.toFixed(2)}</option>`;
  }

  const html = `
    <!DOCTYPE html>
    <html>
    <head>
      <title>Buy Crypto</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container mt-5" style="max-width:600px">
      <h2>Buy Cryptocurrency</h2>
      ${req.session.success ? `<div class='alert alert-success'>${req.session.success}</div>` : ''}
      <form method="POST" action="/buy">
        <div class="mb-3">
          <label>Select Coin</label>
          <select name="crypto" class="form-select" required>
            ${options}
          </select>
        </div>
        <div class="mb-3">
          <label>Amount (e.g. 0.5)</label>
          <input type="number" name="amount" class="form-control" step="0.0001" required>
        </div>
        <button class="btn btn-success w-100">Place Buy Order</button>
      </form>
    </div>
    </body>
    </html>
  `;
  req.session.success = null; // reset
  res.send(html);
});

// Handle form submission
app.post('/buy', authMiddleware, async (req, res) => {
  const { crypto, amount } = req.body;
  const response = await fetch(`https://api.coingecko.com/api/v3/simple/price?ids=${crypto}&vs_currencies=usd`);
  const prices = await response.json();
  const price = prices[crypto].usd;
  const total = price * parseFloat(amount);

  const conn = await pool.getConnection();
  await conn.execute(
    'INSERT INTO transactions (user_id, type, crypto, amount, price, total) VALUES (?, ?, ?, ?, ?, ?)',
    [req.session.user_id, 'buy', crypto, amount, price, total]
  );
  conn.release();

  req.session.success = 'Buy order placed successfully.';
  res.redirect('/buy');
});

// Dummy login route for testing
app.get('/login', (req, res) => {
  req.session.user_id = 1; // fake login
  res.redirect('/buy');
});

app.listen(PORT, () => {
  console.log(`Server running at http://localhost:${PORT}`);
});
