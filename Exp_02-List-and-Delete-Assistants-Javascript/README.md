## Exp_02 - Javascript code to List all assistants and Delete an assistant
<br>

### Objective
- Create a simple web app that connects to the OpenAi API
- Create an assistant
- Create the JS code to list all assistants
- Create JS code to Delete the last assistant that was created

### Notes
- A list of all created assistants can also be viewed on the OpenAi site:<br>
https://platform.openai.com/assistants
- The above link will show you all the assistants that you have created.
- Assistants can be created manually on the OpenAi site instead of programmatically via the API.
- Assistants have the ability to read and process user submitted files. Javascript code can be run from the desktop without having to host the web app on a server, but files can't be uploaded to OpenAi this way. The file has to be transferred from a server to OpenAi, and then attached to the assistant. Therefore, just using Javascript in an app launched from the desktop is not a long term solution. A better approach is to host the app on a shared server and use php on the backend to handle file transfers to OpenAi. That will be covered in the next experiment.
