# Prerequisite
- Docker
- Docker compose
# Setup

It's recommended to [add an alias](https://laravel.com/docs/10.x/sail#configuring-a-shell-alias) for the ease of use.

```
cp .env.example .env
docker compose build
docker compose run laravel.test composer install
```


# Launching the app

To run the app, simply run
```
sail up -d
```
and spawn some workers to process the messages in the queue:
```
sail artisan queue:work
```

It's now accessible in your browser at http://localhost

# Endpoints

## [POST] /job

- **Method**: **POST**
- **URL**: `/`
- **Description**: Analyzes the input text and performs specific tasks based on the provided parameters.

### Payload
```json
{
    "text": "superbe texte",
    "tasks": ["satisfaction", "call_reason", "call_segments", "call_actions", "satisfaction", "summary"]
}
```

- `text`: The input text to be analyzed.
    - Min length: 2
    - Max length: 3000
- `tasks`: An array of task names. Choose one or more from the following:
    - `"satisfaction"`: Evaluate customer satisfaction.
    - `"call_reason"`: Identify the reason for the call.
    - `"call_segments"`: Segment the call into relevant sections.
    - `"call_actions"`: Extract actionable items from the call.
    - `"summary"`: Generate a summary of the call.

### Response

```json
{
    "id": "c45a3a99-450b-48b3-8614-fbf70b5cb363"
}
```

- `id`: A unique job ID assigned to the analysis request.

## [GET] /job/{uuid}

- **Method**: **GET**
- **URL**: `/{uuid}`
- **Description**: Retrieves the analysis results for a specific job identified by its unique UUID.

### Request Parameters

- `{uuid}`: A unique identifier for the job. Replace this with the actual job ID you want to retrieve results for.

### Response

```json
{
    "tasks": [
        {
            "name": "satisfaction",
            "result": {
                "output": 6,
                "processing_time": 2
            }
        },
        {
            "name": "call_reason",
            "result": {
                "output": "Looking for music collab",
                "processing_time": 3
            }
        },
        {
            "name": "call_segments",
            "result": {
                "output": [
                    {
                        "start": 0,
                        "end": 6
                    },
                    {
                        "start": 6,
                        "end": 12
                    }
                ],
                "processing_time": 0
            }
        }
    ]
}
```

- `tasks`: An array of task results.
    - Each task has a `"name"` and a `"result"` property.
    - `"output"`: The specific output related to the task (e.g., satisfaction score, call reason, call segments, etc.).
    - `"processing_time"`: The time taken to process the task.

# Unit & Feature test

I order to run the unit tests just run `sail composer test`

# Stress testing

## Vendor modification
As I was trying to implement the stress tests, I realize the current pest-plugin-stressless vendor I use requires modification for this to work. I've created a PR in the repository. In the meantime, you can apply this simple modification locally to make it work:

Modify line 45 in `vendor\pestphp\pest-plugin-stressless\bin\run.js`:
```
45          -   headers: { 'user-agent': userAgent },
            +   headers: { 'Content-Type': 'application/json', 'user-agent': userAgent },
```

## Note
If you don't want to pollute your main DB, you can set `DB_DATABASE` to `testing` in your `.env` and restart sail.

```
sail down
# perform .enc DB_DATABASE change
sail up -d
```
## Running the tests
```
sail composer test:stress
```
