# Project Name

Expense Tracker App Backend

### Prerequisites
List what is needed to install and run the project, 

- Docker

# Dependencies

- PHP v8.2
- MySQL v8.0
- phpMyAdmin

# How to install
- To run this project API's you will have to install docker first. Once, the docker is installed open the root directory of this project and then type

```
docker-compose build
docker-compose up
```

# How to call API's

# Sign Up API Endpoint
- http://localhost:8000/api/v1/signup
- Req

```
{
    "username": "admin",
    "email": "admin",
    "password": "password123",
    "role" : "admin"
}
```

# Sign In  API Endpoint
- http://localhost:8000/api/v1/signin
- Req

```
{
    "email": "admin@gmail.com",
    "password": "password123"
}
```

# Validate JWT  API Endpoint
- http://localhost:8000/api/v1/validate
- Put the JWT token into the Auth type. You can get the token from the Sign In API

```
Auth Type: Bearer Token

Example:- eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJ1c2VySWQiOjEsInVzZXJuYW1lIjoiam9obl9kb2UiLCJyb2xlIjoidXNlciIsImlhdCI6MTczMjAyMTE2MywiZXhwIjoxNzMyMDI0NzYzfQ.U6xxkNC2fWoPMYJFD0SsU-fPV6Gp0-N_Cm0wTy9ooWKfzF2i73cpJZeyFjJlEFxez3CV9FUdCArgN3OTUluD-HTK3XwLcK-yVNvYQqzt6gb55pVd2rfQZXSXPKCfOodK_zMM54cpn-9Ns-ms6RB0rB62RGrWWu8yd4NZWwZSu1g
```

# Create Expense API Endpoint
- http://localhost:8000/api/v1/createExpense
- Req
- Send token in the header 

```
{
  "amount": 0.1,
  "category": "Restaurant",
  "description": "Albaik",
  "expense_date": "2023-11-01"
}
```

# Edit Expense API Endpoint
- http://localhost:8000/api/v1/editExpense?id=12
- Req
- Send token in the header 
- Send the param in the API eg. 12

```
{
  "amount": 534340.00,
  "category": "Travel",
  "description": "Cab fare",
  "expense_date": "2024-11-17"
}
```

# Get User Expense API Endpoint
- http://localhost:8000/api/v1/getExpense
- Send token in the header 

# Get All Expenses API Endpoint
- http://localhost:8000/api/v1/getAllExpenses
- Send token in the header 

# Delete Expense API Endpoint
- http://localhost:8000/api/v1/deleteExpense?id=13
- Send token in the header 
- Send the param in the API eg. 13



# phpMyAdmin Password

- h4Si3fiVeADnXy2

