import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Checkbox from '@/Components/Checkbox';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Show({ task }) {
    function confirmTaskDelete(id) {
        if (confirm('Are you sure to delete this Task?')) {
            axios.post(route('tasks.destroy', id), { _method: 'DELETE' })
                .then(res => setTimeout(() => {
                    window.location.href = route('tasks.index')
                }, 1000))
                .catch(error => console.error(error));
        }
    };

    function formatDate(value) {
        return new Date(value).toISOString().split('T')[0];
    }

    const toggleCompleted = (id, e) => {
        axios.post(route('tasks.completed', id))
            .then(setData('is_completed', e.target.checked))
            .catch(error => { console.log(error); });
    };

    const { data, setData } = useForm({
        id: task.id,
        name: task.name,
        description: task.description,
        is_completed: task.is_completed,
        expired_at: task.expired_at ? formatDate(task.expired_at) : null,
    });

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Tasks details
                </h2>
            }
        >
            <Head title="Tasks details" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <Link href={route('tasks.index')} method="get" as="button"
                                className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                &lt;&nbsp;BACK&nbsp;TO&nbsp;TASKS
                            </Link><br /><br />
                            Name: {data.name}<br />
                            Description: {data.description}<br />
                            Completed: <Checkbox
                                name="is_completed"
                                checked={data.is_completed}
                                onChange={(e) => toggleCompleted(data.id, e)}
                            /><br />
                            Due date: {data.expired_at ? data.expired_at : 'No date set'}<br />
                            <Link
                                href={route('tasks.edit', data.id)}
                                method="get"
                                as="button"
                                className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                EDIT
                            </Link>
                            <Link as="button" onClick={() => confirmTaskDelete(data.id)}
                                className="px-2 underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                DELETE
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
