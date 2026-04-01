-- INF653 Midterm Project — Quotations REST API
-- Database: quotesdb

CREATE TABLE IF NOT EXISTS authors (
    id     SERIAL       PRIMARY KEY,
    author VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS categories (
    id       SERIAL       PRIMARY KEY,
    category VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS quotes (
    id          SERIAL  PRIMARY KEY,
    quote       TEXT    NOT NULL,
    author_id   INT     NOT NULL REFERENCES authors(id),
    category_id INT     NOT NULL REFERENCES categories(id)
);

-- ─── Seed Data ────────────────────────────────────────────────────────────────

INSERT INTO authors (author) VALUES
    ('Kurt Cobain'),
    ('David Bowie'),
    ('Freddie Mercury'),
    ('Jimi Hendrix'),
    ('Bob Marley');

INSERT INTO categories (category) VALUES
    ('Motivation'),
    ('Wisdom'),
    ('Humor'),
    ('Life'),
    ('Success');

INSERT INTO quotes (quote, author_id, category_id) VALUES
    -- Kurt Cobain (1)
    ('Punk is musical freedom. It''s saying, doing and playing what you want. In Webster''s terms, ''nirvana'' means freedom from pain, suffering and the external world, and that''s pretty close to my definition of punk rock.', 1, 1),
    ('I''d rather be hated for who I am than loved for who I am not.', 1, 2),
    ('The duty of youth is to challenge corruption.', 1, 1),
    ('I''m not like them, but I can pretend.', 1, 3),
    ('We have no right to express an opinion until we know all of the answers.', 1, 4),

    -- David Bowie (2)
    ('I don''t know where I''m going from here, but I promise it won''t be boring.', 2, 4),
    ('Aging is an extraordinary process where you become the person you always should have been.', 2, 2),
    ('I always had a repulsive need to be something more than human.', 2, 4),
    ('Tomorrow belongs to those who can hear it coming.', 2, 5),
    ('I re-invented my image so many times that I''m in denial that I was originally an accountant.', 2, 3),

    -- Freddie Mercury (3)
    ('The most important thing is to live a fabulous life. As long as it''s fabulous I don''t care how long it is.', 3, 4),
    ('I won''t be a rock star. I will be a legend.', 3, 5),
    ('Money may not buy happiness, but it can damn well give it!', 3, 3),
    ('I always knew I was a star. And now, the rest of the world seems to agree with me.', 3, 5),
    ('I''m just a musical prostitute, my dear.', 3, 3),

    -- Jimi Hendrix (4)
    ('Music doesn''t lie. If there is something to be changed in this world, then it can only happen through music.', 4, 1),
    ('Knowledge speaks, but wisdom listens.', 4, 2),
    ('I''m the one that''s got to die when it''s time for me to die, so let me live my life the way I want to.', 4, 4),
    ('When the power of love overcomes the love of power, the world will know peace.', 4, 2),
    ('I''ve been imitated so well I''ve heard people copy my mistakes.', 4, 3),

    -- Bob Marley (5)
    ('One good thing about music, when it hits you, you feel no pain.', 5, 4),
    ('The truth is, everyone is going to hurt you. You just got to find the ones worth suffering for.', 5, 2),
    ('Love the life you live. Live the life you love.', 5, 4),
    ('Don''t gain the world and lose your soul; wisdom is better than silver and gold.', 5, 2),
    ('Emancipate yourselves from mental slavery; none but ourselves can free our minds.', 5, 1);
