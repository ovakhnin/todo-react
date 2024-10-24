import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Checkbox from '@/Components/Checkbox';
import { Head, Link } from '@inertiajs/react';
import React, { useState } from 'react';

export default function List({ tasks }) {
    const confirmTaskDelete = (id) => {
        if (confirm('Are you sure to delete this Task?')) {
            axios.post(route('tasks.destroy', id), { _method: 'DELETE' })
                .then()
                .catch(error => console.error(error));
        }
    };

    function formatDate(value) {
        return new Date(value).toISOString().split('T')[0];
    }

    let tempList = tasks.map((task) => {
        if (task.is_completed > 0) {
            return (`is_completed_${task.id}`)
        }
    });

    const [selectedCheckboxes, setSelectedCheckboxes] = useState(tempList);

    const handleCheckboxChange = (value, id) => {
        axios.post(route('tasks.completed', id))
            .then(res => {
                if (selectedCheckboxes.includes(value)) {
                    setSelectedCheckboxes(selectedCheckboxes.filter((v) => v !== value));
                } else {
                    setSelectedCheckboxes([...selectedCheckboxes, value]);
                }
            })
            .catch(error => { console.log(error); });
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Tasks list
                </h2>
            }
        >
            <Head title="Tasks list" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <Link
                                href={route('tasks.create')}
                                className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                ADD NEW TASK
                            </Link><br />

                            {tasks.map((task) => {
                                return (
                                    <div key={task.id} className="py-4">
                                        <Link
                                            href={route('tasks.show', task.id)}
                                            method="get"
                                            as="button"
                                            className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Name: {task.name}
                                        </Link><br />
                                        Description: {task.description}<br />
                                        Completed: <Checkbox
                                            id={task.id}
                                            name={`is_completed_${task.id}`}
                                            checked={selectedCheckboxes.includes(`is_completed_${task.id}`)}
                                            onChange={() => handleCheckboxChange(`is_completed_${task.id}`, task.id)}
                                        /><br />
                                        Due date: {task.expired_at ? formatDate(task.expired_at) : 'No date set'}<br />
                                        <Link
                                            href={route('tasks.edit', task.id)}
                                            method="get"
                                            as="button"
                                            className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            EDIT
                                        </Link>
                                        <Link as="button" onClick={() => confirmTaskDelete(task.id)}
                                            className="px-2 underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            DELETE
                                        </Link>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
