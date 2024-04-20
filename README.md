This API is written in order to be part of some web app where user would be able to make profile and post recipes and comment other recipes and rate it and so on, so on..

# Connected to user:

## _**How to register user?**_

``` bash
Endpoint: root/api/register
Method: POST
Description: Register a new user.
Request Body:
    {
        "name": "Name of the user".
        "email": "Email address of the user".
        "password": "Password for the user account"
        "photo" : "photo"
    }
Required:
name, email, password and photo. Password has to be at least 8 char long!
Response:
If user already exist, you'll get:
    {
        "message" : "You already have account"
    }
If everything is ok, you'll get:
    {
        "message" : "Created!"
    }

 ```

## _**How to login user?**_

``` bash
Endpoint: root/api/login
Method: POST
Description: Login an existing user.
Request Body:
       {
        "email": "Email address of the user".
        "password": "Password for the user account".
        }   
Response:
If user does not exist:
    {
        "message" : "User does not exist!"
    }
If credentials are wrong:
    {
        "message" : "Wrong credentials!"
    }
If everything is ok:
Returns an authentication token.
    {
        "token" : token
    }

 ```

## _**How to logout user?**_

``` bash
Endpoint: root/api/logout
Method: DELETE
Description: Log out the authenticated user.
Request Header:
    Authorization: Bearer token
Response:
    {
        "message" : "Logged out!"
    }

 ```

# Connected to recipes:

## Get all recipes:

``` bash
Endpoint: root/api/recipe
Method: GET
Description: Retrieve all recipes
Request Header:
    Authorization: Bearer token
Search:
    optional parameters:
        include - additional relations that you want to include:
            can be: user - it will include details of recipe owner
                    user.comments - it will include details of recipe owner with array of comments posted by owner
                    comments - it will include array of comments posted on recipe
        category - additional filter parameter
            can be: other, pasta or cookie
                    Recipe that will be returned are only from category that you searched
                    !By default it will include all category.
        text - additional filter parameter:
              - Return will include all recipes which title or content include value from text field.
Response: Returns all recipes:
    {
        "recipes" : [list of recipes]
    }
Recipe will be returned in format:
        {
            "id": ,
            "user_id": ,
            "title": ,
            "content": ,
            "category":,
            "avg_rate": ,
            "num_rate": ,
            "components": [
                "",
                "",
                ""
            ]
            "user" [if user is included]: {
                 "id":,
                 "name" :
                 "email" :
                 "comments" [if user.comments is included]: [
                 ]
             }
             "comments": [if comments is included]:[
             ]
        }

 ```

## Get all recipes posted by specific user:

``` bash
Endpoint: root/api/my-recipe
Method: GET
Description: Retrieve all recipes posted by specific user
Request Header:
    Authorization: Bearer token of that specific user
Search:
    optional parameters:
        include - additional relations that you want to include:
            can be: user - it will include details of recipe owner
                    user.comments - it will include details of recipe owner with array of comments posted by owner
                    comments - it will include array of comments posted on recipe
        category - additional filter parameter
            can be: other, pasta or cookie
                    Recipe that will be returned are only from category that you searched
                    !By default it will include all category.
        text - additional filter parameter:
              - Return will include all recipes which title or content include value from text field.
Response: Returns all recipes:
    {
        "recipes" : [list of recipes]
    }
Recipe will be returned in format:
        {
            "id": ,
            "user_id": ,
            "title": ,
            "content": ,
            "category":,
            "avg_rate": ,
            "num_rate": ,
            "components": [
                "",
                "",
                ""
            ]
            "user" [if user is included]: {
                 "id":,
                 "name" :
                 "email" :
                 "comments" [if user.comments is included]: [
                 ]
             }
             "comments": [if comments is included]:[
             ]
        }

 ```

## Get one recipe:

``` bash
Endpoint: root/api/recipe/{idOfRecipe}
Method: GET
Description: Retrieve one recipe
Request Header:
    Authorization: Bearer token
Search:
    optional parameters:
        include - additional relations that you want to include:
            can be: user - it will include details of recipe owner
                    user.comments - it will include details of recipe owner with array of comments posted by owner
                    comments - it will include array of comments posted on recipe
Response: 
If recipe does not exist:
    {
        "error" : "Recipe does not exist"
    }
Returns one recipe:
    {
        "recipes" : [Recipe]
    }
Recipe will be returned in format:
        {
            "id": ,
            "user_id": ,
            "title": ,
            "content": ,
            "category":,
            "avg_rate": ,
            "num_rate": ,
            "components": [
                "",
                "",
                ""
            ]
            "user" [if user is included]: {
                 "id":,
                 "name" :
                 "email" :
                 "comments" [if user.comments is included]: [
                 ]
             }
             "comments": [if comments is included]:[
             ]
        }

 ```

## Create recipe:

``` bash
Endpoint: root/api/my-recipe
Method: POST
Description: Create recipe
Request Header:
    Authorization: Bearer token
Request Body:
       {
        "title": "Email address of the user",
        "content": "Password for the user account",
        "components": "Comp1,comp2,comp3,...",
        "category": "category"
    }
 required:
     title - string(max:255),content - string,
     components - string where components are separated by comma
     category is string and has to be one of [other, pasta, cookie]"
Response: 
If request body is not correct:
    status code: 404
Returns created recipe:
    {
        "recipes" : [Recipe]
    }

 ```

## Update specific recipe:

``` bash
Warning: Users can only update recipes posted by themself!
Endpoint: root/api/my-recipe/{idOfRecipe}
Method: POST
Description: Update one recipe
Request Header:
    Authorization: Bearer token
Request Body:
       {
        "title": "Email address of the user",
        "content": "Password for the user account",
        "components": "Comp1,comp2,comp3,...",
        "category": "category"
    }
 properties:
     title - string(max:255),content - string,
     components - string where components are separated by comma
     category is string and has to be one of [other, pasta, cookie]"
Response: 
If recipe does not exist:
    status code: 204
If request body is empty:
    {
        "error" : "You haven't sent anything"
    }
Returns updated recipe:
    {
        "recipes" : [Recipe]
    }

 ```

## Delete specific recipe:

``` bash
Warning: Users can only delete recipes posted by themself!
Endpoint: root/api/my-recipe/{idOfRecipe}
Method: DELETE
Description: Update one recipe
Request Header:
    Authorization: Bearer token
Response: 
If recipe does not exist:
    status code: 400
Else:
    status code: 204

 ```

# Connected to comments:

## Create comment:

``` bash
Endpoint: root/api/recipe/{idOfRecipe}/comment
Method: POST
Description: Create comment
Request Header:
    Authorization: Bearer token of comment creator
Request Body:
    {
        "content" : "content..."
    }
    required:
        content - string
Response: 
If recipe does not exist:
    status code: 400
If content is not sent:
    status code: 404
Else:
    {
        "comments" : {comment}
    }

 ```

## Get all comments from one recipe:

``` bash
Endpoint: root/api/recipe/{idOfRecipe}/comment
Method: GET
Description: Get all comments
Request Header:
    Authorization: Bearer token
Search:
    optional parameters:
        include - additional relations that you want to include:
            can be: user - it will include details of comment owner
                    user.recipes - it will include details of recipe owner with array of recipes posted by owner
                    recipe - it will include details of recipe on which comment is posted
Response: 
    {
        "comments" : {comments}
    }
Comment will be returned in format:
    {
        "id":,
        "user_id":,
        "recipe_id":,
        "content":,
         "user" [if user is included]: {
             "id":,
             "name" :
             "email" :
             "recipes" [if user.recipes is included]: [
                 ]
          },
         "recipe" : {
         }
    }

 ```

## Get specific comment:

``` bash
Endpoint: root/api/recipe/{idOfRecipe}/comment/{idOfComment}
Method: GET
Description: Get specific comment
Request Header:
    Authorization: Bearer token
Response: 
    {
        "comments" : {comments}
    }
Comment will be returned in format:
    {
        "id":,
        "user_id":,
        "recipe_id":,
        "content": 
    }

 ```

## Update comment:

``` bash
Warning: Users can only update comments posted by themself!
Endpoint: root/api/recipe/{idOfRecipe}/comment/{idOfComment}
Method: PUT
Description: Get specific comment
Request Header:
    Authorization: Bearer token
Request Body:
    {
        "content" : "content..."
    }
    properties:
        content - string
Response:
If you haven't sent content:
    {
        "error" : "No content!"
    }, status code: 400
Else:
    {
        "comments" : {comments}
    }, status code: 200

 ```

## Delete comment:

``` bash
Warning: Users can only deletecomments posted by themself!
Endpoint: root/api/recipe/{idOfRecipe}/comment/{idOfComment}
Method: DELETE
Description: Delete comment
Request Header:
    Authorization: Bearer token
Response:
If comment does not exist:
    status code: 400
Else:
    status code: 204

 ```

# Connected to rates:

# Create rate:

``` bash
Endpoint: root/api/recipe/{idOfRecipe}/my-rate
Method: POST
Description: Create rate
Request Header:
    Authorization: Bearer token of comment creator
Request Body:
    {
        "rate" : number
    }
    required:
        rate - integer
Response: 
If recipe does not exist:
    status code: 400
If rate is not sent:
    {
        "error": "You did not send rate"
    }, status code: 400
Else:
    status code: 201

 ```

# See rate of specific user on recipe:

``` bash
Endpoint: root/api/recipe/{idOfRecipe}/my-rate
Method: POST
Description: See rate
Request Header:
    Authorization: Bearer token of comment creator
Response: 
If recipe does not exist:
    status code: 400
Else:
    {
        "rate" : rate
    }, status code: 200

 ```

# Update rate of specific user on recipe:

``` bash
Endpoint: root/api/recipe/{idOfRecipe}/my-rate
Method: PUT
Description: See rate
Request Header:
    Authorization: Bearer token of comment creator
Request Body:
    {
        "rate" : number
    }
    properties:
        rate - integer
Response: 
If recipe does not exist:
    status code: 400
If rate is not sent:
    {
        "error": "You did not send rate"
    }, status code: 400
Else:
    status code: 200

 ```
