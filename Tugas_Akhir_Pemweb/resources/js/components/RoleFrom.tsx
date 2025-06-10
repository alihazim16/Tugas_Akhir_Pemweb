import React from 'react';

interface RoleFormProps {
  roleName: string;
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
  onSubmit: () => void;
  buttonText?: string;
}

const RoleForm: React.FC<RoleFormProps> = ({ roleName, onChange, onSubmit, buttonText = 'Submit' }) => {
  return (
    <div className="mb-4">
      <input
        type="text"
        className="border p-2 mr-2"
        value={roleName}
        onChange={onChange}
        placeholder="Role name"
      />
      <button onClick={onSubmit} className="bg-blue-500 text-white px-4 py-2">
        {buttonText}
      </button>
    </div>
  );
};

export default RoleForm;
