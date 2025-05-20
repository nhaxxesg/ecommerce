import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import CreateMenu from "@/Components/CreateMenu";

function Crear() {
    return (
        <AuthenticatedLayout
           header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Menus
                </h2>
            }
        >
            <CreateMenu/>
        </AuthenticatedLayout>
    );
}

export default Crear;