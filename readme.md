#Simple PHP Encryption Static Class.
 
 Encrypt and decrypt any message using two secret phrases.
 
The main advantage over the built-in encryption methods is that the encrypted message has the same length of the original message, so it's often very short and it's ideal for creating authentication keys or access tokens and passing them with GET or POST requests.
 
This algorithm is very basic stuff, use it at your own risk.

## Examples

Encrypt any message. It will return a string of the same length of the sentence encrypted

> Encryption::encrypt("My Secret Sentence")

Decrypt a message previously encrypted

> Encryption::decrypt("EaNQhIuWVH6enZ+dh6ipl6GliFfSp4GknpqFhc6lsbbLyYN+")

Sign a message. It will ouput a 20 characters long string.

> Encryption::sign("Andrew")

Detect if the signature of the message is valid. It return a boolean, true if it's valid, false otherwise.

> Encryption::hasValidSignature("Andrew", "aoi28CA91ncuaF910s1E")

### Example: Token generation

In the *examples* folder you will find a simple Token generation case.
If you want to create a simple access token for a user, you may want to grab the user's id and the current timestamp, sign them together, encrypt them and pass this generated token to the user.

When the user append this token to a request, you can decrypt it, verify the signature, check if it's still valid (in the example the token has a 1 week validity) and use user's id.