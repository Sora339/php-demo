"use client";

import { useEffect, useState } from "react";

type Task = {
  id: number;
  title: string;
  completed: boolean;
};

export default function Page() {
  const [tasks, setTasks] = useState<Task[]>([]);
  const [newTaskTitle, setNewTaskTitle] = useState("");

  const fetchTasks = async () => {
    const response = await fetch(
      `${process.env.NEXT_PUBLIC_API_URL}/get-tasks.php`
    );
    const data: Task[] = await response.json();
    console.log(data)
    setTasks(data);
  };

  const addTask = async (title: string) => {
    try {
      await fetch(`${process.env.NEXT_PUBLIC_API_URL}/add-task.php`, {
        method: "POST",
        headers: { "content-Type": "application/json" },
        body: JSON.stringify({ title, completed: false }),
      });
      await fetchTasks(); //タスク表示の更新
    } catch (error) {
      console.error("failed to fetch");
    }
  };

  const completeTask = async (id: number) => {
    console.log(id)
    try {
        await fetch(`${process.env.NEXT_PUBLIC_API_URL}/update-task.php`, {
            method: "PUT",
            headers: { "content-Type": "application/json" },
            body: JSON.stringify({ id, completed: true }),
        })
        await fetchTasks();
    } catch (error) {
        console.error("failed to fetch")
    }

  }

  // 初回レンダリング時のみタスク一覧を取得
  useEffect(() => {
    console.log("Fetching tasks");
    fetchTasks();
  }, []);

  return (
    <div>
      <h1>To-Do List</h1>
      <ul>
        {tasks.map((task) => (
          <li key={task.id}>
            <span
              style={{
                textDecoration: task.completed ? "line-through" : "none",
              }}
            >
            <input type="checkbox" onChange={() => completeTask(task.id)}/>
              {task.title}
            </span>
          </li>
        ))}
      </ul>
      <div>
        <input
          type="text"
          value={newTaskTitle}
          onChange={(e) => setNewTaskTitle(e.target.value)}
          placeholder="New task title"
        />
        <button onClick={() => addTask(newTaskTitle)}>Add Task</button>
      </div>
    </div>
  );
}
