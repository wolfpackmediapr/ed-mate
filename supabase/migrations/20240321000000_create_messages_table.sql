-- Create messages table
CREATE TABLE IF NOT EXISTS messages (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    sender_id UUID NOT NULL REFERENCES auth.users(id) ON DELETE CASCADE,
    receiver_id UUID NOT NULL REFERENCES auth.users(id) ON DELETE CASCADE,
    content TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc'::text, NOW()) NOT NULL
);

-- Create indexes for better query performance
CREATE INDEX IF NOT EXISTS idx_messages_sender_id ON messages(sender_id);
CREATE INDEX IF NOT EXISTS idx_messages_receiver_id ON messages(receiver_id);
CREATE INDEX IF NOT EXISTS idx_messages_created_at ON messages(created_at);

-- Create RLS (Row Level Security) policies
ALTER TABLE messages ENABLE ROW LEVEL SECURITY;

-- Policy for inserting messages (users can only send messages as themselves)
CREATE POLICY "Users can insert messages as themselves"
    ON messages FOR INSERT
    WITH CHECK (auth.uid() = sender_id);

-- Policy for viewing messages (users can only see messages they sent or received)
CREATE POLICY "Users can view their own messages"
    ON messages FOR SELECT
    USING (auth.uid() = sender_id OR auth.uid() = receiver_id);

-- Policy for updating messages (users can only mark messages as read if they are the receiver)
CREATE POLICY "Users can update messages they received"
    ON messages FOR UPDATE
    USING (auth.uid() = receiver_id)
    WITH CHECK (auth.uid() = receiver_id);

-- Create function to update updated_at timestamp
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = TIMEZONE('utc'::text, NOW());
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Create trigger to automatically update updated_at
CREATE TRIGGER update_messages_updated_at
    BEFORE UPDATE ON messages
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column(); 