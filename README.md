# Access Key API

*This project was built for learning API development, authentication, and authorization by implementing the authentication system from scratch instead of relying on Laravel's pre-built authentication system.*

## Postman Procedure

1. Generate a **KEY** by sending a `GET` request to `/api/generate_key`.
2. Authenticate using the generated KEY by sending a `POST` request to `/api/login`.

   * Use form-data with:

     * Key: `key`
     * Value: `GENERATED_KEY`
3. Copy the authorization token returned in the `/api/login` JSON response.
4. In Postman, set the Authorization type to **Bearer Token** and enter the token.
5. Send a `GET` request to `/api/my_key`.
6. The KEY's status and lifetime will be displayed in the JSON response.

## API Endpoints

| Method | Endpoint            | Description                                         |
| ------ | ------------------- | --------------------------------------------------- |
| GET    | `/api/generate_key` | Generate a new access KEY                           |
| POST   | `/api/login`        | Authenticate using a KEY and receive a bearer token |
| GET    | `/api/my_key`       | View the authenticated KEY's status and lifetime    |

#