import { useRef, FormEvent, useState } from "react";

export default function Chat() {
  const messagesRef = useRef<HTMLInputElement>(null);
  const [messages, setMessages] = useState<Array<String>>(["Hello world", "How are you?"]);
  const [submit, setSubmit] = useState(false);
  
  function handleSubmit(e: FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setSubmit(true);

    const newMessage = messagesRef.current?.value.trim();

    if (!newMessage) {
      return;
    }
    setMessages([...messages, newMessage]);
  }
  return (
    <div className="flex flex-1 flex-col px-4 pb-4 pt-2">
      {messages.length === 0 ? (
        <div className="flex flex-1 items-center justify-center">
          <h1>What do you want to learn?</h1>
        </div>
      ) : (
        <div className="flex-col flex-1 items-center justify-center">
          <div className="flex-row">
            {messages.map((message, index) => (
              <div key={index} className="mb-2">
                <div className="text-gray-700 border-2 border-gray-200 color-gray-500 bg-gray-100 border-opacity-50 rounded px-3 py-2 ">{message}</div>
              </div>
            ))}
          </div>
        </div>
      )}

      <form onSubmit={handleSubmit}>
        <div className="sticky bottom-0 flex w-full justify-center bg-background">
          <input
            id="qurry"
            placeholder="Type your question here..."
            className="flex-1 rounded-md border border-blue-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            ref={messagesRef}
          />

          <button
            type="submit"
            className="ml-2 rounded-md bg-black px-4 py-2 text-white hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300"
          >
            Send
          </button>
        </div>
      </form>
    </div>
  );
}
